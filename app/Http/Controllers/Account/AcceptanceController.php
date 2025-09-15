<?php

namespace App\Http\Controllers\Account;

use App\Events\CheckoutAccepted;
use App\Events\CheckoutDeclined;
use App\Events\ItemAccepted;
use App\Events\ItemDeclined;
use App\Http\Controllers\Controller;
use App\Mail\CheckoutAcceptanceResponseMail;
use App\Models\CheckoutAcceptance;
use App\Models\Company;
use App\Models\Contracts\Acceptable;
use App\Models\Setting;
use App\Models\User;
use App\Models\AssetModel;
use App\Models\Accessory;
use App\Models\License;
use App\Models\Component;
use App\Models\Consumable;
use App\Notifications\AcceptanceAssetAcceptedNotification;
use App\Notifications\AcceptanceAssetAcceptedToUserNotification;
use App\Notifications\AcceptanceAssetDeclinedNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\SettingsController;
use Carbon\Carbon;
use \Illuminate\Contracts\View\View;
use \Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use TCPDF;
use App\Helpers\Helper;

class AcceptanceController extends Controller
{
    /**
     * Show a listing of pending checkout acceptances for the current user
     */
    public function index() : View
    {
        $acceptances = CheckoutAcceptance::forUser(auth()->user())->pending()->get();
        return view('account/accept.index', compact('acceptances'));
    }

    /**
     * Shows a form to either accept or decline the checkout acceptance
     *
     * @param  int  $id
     */
    public function create($id) : View | RedirectResponse
    {
        $acceptance = CheckoutAcceptance::find($id);


        if (is_null($acceptance)) {
            return redirect()->route('account.accept')->with('error', trans('admin/hardware/message.does_not_exist'));
        }

        if (! $acceptance->isPending()) {
            return redirect()->route('account.accept')->with('error', trans('admin/users/message.error.asset_already_accepted'));
        }

        if (! $acceptance->isCheckedOutTo(auth()->user())) {
            return redirect()->route('account.accept')->with('error', trans('admin/users/message.error.incorrect_user_accepted'));
        }

        if (! Company::isCurrentUserHasAccess($acceptance->checkoutable)) {
            return redirect()->route('account.accept')->with('error', trans('general.error_user_company'));
        }

        return view('account/accept.create', compact('acceptance'));
    }

    /**
     * Stores the accept/decline of the checkout acceptance
     *
     * @param  Request $request
     * @param  int  $id
     */
    public function store(Request $request, $id) : RedirectResponse
    {
        $acceptance = CheckoutAcceptance::find($id);

        if (is_null($acceptance)) {
            return redirect()->route('account.accept')->with('error', trans('admin/hardware/message.does_not_exist'));
        }

        if (! $acceptance->isPending()) {
            return redirect()->route('account.accept')->with('error', trans('admin/users/message.error.asset_already_accepted'));
        }

        if (! $acceptance->isCheckedOutTo(auth()->user())) {
            return redirect()->route('account.accept')->with('error', trans('admin/users/message.error.incorrect_user_accepted'));
        }

        if (! Company::isCurrentUserHasAccess($acceptance->checkoutable)) {
            return redirect()->route('account.accept')->with('error', trans('general.insufficient_permissions'));
        }

        if (! $request->filled('asset_acceptance')) {
            return redirect()->back()->with('error', trans('admin/users/message.error.accept_or_decline'));
        }

        /**
         * Check for the signature directory
         */
        if (! Storage::exists('private_uploads/signatures')) {
            Storage::makeDirectory('private_uploads/signatures', 775);
        }

        /**
         * Check for the eula-pdfs directory
         */
        if (! Storage::exists('private_uploads/eula-pdfs')) {
            Storage::makeDirectory('private_uploads/eula-pdfs', 775);
        }


        $item = $acceptance->checkoutable_type::find($acceptance->checkoutable_id);
        $display_model = '';
        $pdf_view_route = '';
        $pdf_filename = 'accepted-eula-'.date('Y-m-d-h-i-s').'.pdf';
        $sig_filename='';

        if ($request->input('asset_acceptance') == 'accepted') {

            if (Setting::getSettings()->require_accept_signature == '1') {

                // The item was accepted, check for a signature
                if ($request->filled('signature_output')) {
                    $sig_filename = 'siglog-' . Str::uuid() . '-' . date('Y-m-d-his') . '.png';
                    $data_uri = $request->input('signature_output');
                    $encoded_image = explode(',', $data_uri);
                    $decoded_image = base64_decode($encoded_image[1]);
                    Storage::put('private_uploads/signatures/' . $sig_filename, (string)$decoded_image);

                    // No image data is present, kick them back.
                    // This mostly only applies to users on super-duper crapola browsers *cough* IE *cough*
                } else {
                    return redirect()->back()->with('error', trans('general.shitty_browser'));
                }
            }

            $assigned_user = User::find($acceptance->assigned_to_id);


            /**
             * Gather the data for the PDF. We fire this whether there is a signature required or not,
             * since we want the moment-in-time proof of what the EULA was when they accepted it.
             */
            $branding_settings = SettingsController::getPDFBranding();

            $path_logo = "";

            // Check for the PDF logo path and use that, otherwise use the regular logo path
            if (!is_null($branding_settings->acceptance_pdf_logo)) {
                $path_logo = public_path() . '/uploads/' . $branding_settings->acceptance_pdf_logo;
            } elseif (!is_null($branding_settings->logo)) {
                $path_logo = public_path() . '/uploads/' . $branding_settings->logo;
            }
            
            $data = [
                'item_tag' => $item->asset_tag,
                'item_model' => $display_model,
                'item_serial' => $item->serial,
                'item_status' => $item->assetstatus?->name,
                'eula' => $item->getEula(),
                'note' => $request->input('note'),
                'check_out_date' => Carbon::parse($acceptance->created_at)->format('Y-m-d H:i:s'),
                'accepted_date' => Carbon::parse($acceptance->accepted_at)->format('Y-m-d H:i:s'),
                'assigned_to' => $assigned_user->display_name,
                'company_name' => $branding_settings->site_name,
                'signature' => ($sig_filename) ? storage_path() . '/private_uploads/signatures/' . $sig_filename : null,
                'logo' => $path_logo,
                'date_settings' => $branding_settings->date_display_format,
                'admin' => auth()->user()->present()?->fullName,
                'qty' => $acceptance->qty ?? 1,
            ];

            // set some language dependent data:
            $lg = Array();
            $lg['a_meta_charset'] = 'UTF-8';
            $lg['w_page'] = 'page';

            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->setRTL(false);
            $pdf->setLanguageArray($lg);
            $pdf->SetFontSubsetting(true);
            $pdf->SetCreator('Snipe-IT');
            $pdf->SetAuthor($data['assigned_to']);
            $pdf->SetTitle('Asset Acceptance: '.$data['item_tag']);
            $pdf->SetSubject('Asset Acceptance: '.$data['item_tag']);
            $pdf->SetKeywords('Snipe-IT, assets, acceptance, eula', 'tos');
            $pdf->SetFont('dejavusans', '', 8, '', true);
            $pdf->SetPrintHeader(false);
            $pdf->SetPrintFooter(false);
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

            $pdf->AddPage();
            $pdf->writeHTML('<img src="'.$path_logo.'" height="30">', true, 0, true, 0, '');

            if ($data['item_serial']) {
                $pdf->writeHTML("<strong>" . trans('general.asset_tag') . '</strong>: ' . $data['item_tag'], true, 0, true, 0, '');
            }
            $pdf->writeHTML("<strong>".trans('general.asset_model').'</strong>: '.$data['item_model'], true, 0, true, 0, '');
            if ($data['item_serial']) {
                $pdf->writeHTML("<strong>".trans('admin/hardware/form.serial').'</strong>: '.$data['item_serial'], true, 0, true, 0, '');
            }
            $pdf->writeHTML("<strong>".trans('general.assigned_date').'</strong>: '.$data['check_out_date'], true, 0, true, 0, '');
            $pdf->writeHTML("<strong>".trans('general.assignee').'</strong>: '.$data['assigned_to'], true, 0, true, 0, '');
            $pdf->Ln();

            // Break the EULA into lines based on newlines, and check each line for RTL or CJK characters
            $eula_lines = preg_split("/\r\n|\n|\r/", $item->getEula());

            foreach ($eula_lines as $eula_line) {
                Helper::hasRtl($eula_line) ? $pdf->setRTL(true) : $pdf->setRTL(false);
                Helper::isCjk($eula_line) ? $pdf->SetFont('cid0cs', '', 9) : $pdf->SetFont('dejavusans', '', 8, '', true);

                $pdf->writeHTML(Helper::parseEscapedMarkedown($eula_line), true, 0, true, 0, '');
            }
            $pdf->Ln();
            $pdf->Ln();
            $pdf->setRTL(false);
            $pdf->writeHTML('<br><br>', true, 0, true, 0, '');

            if ($data['note'] != null) {
                Helper::isCjk($data['note']) ? $pdf->SetFont('cid0cs', '', 9) : $pdf->SetFont('dejavusans', '', 8, '', true);
                $pdf->writeHTML("<strong>".trans('general.notes') . '</strong>: ' . $data['note'], true, 0, true, 0, '');
                $pdf->Ln();
            }

            if ($data['signature'] != null) {

                $pdf->writeHTML('<img src="'.$data['signature'].'" style="max-width: 600px;">', true, 0, true, 0, '');
                $pdf->writeHTML('<hr>', true, 0, true, 0, '');
            }

            $pdf->writeHTML("<strong>".trans('general.accepted_date').'</strong>: '.$data['accepted_date'], true, 0, true, 0, '');


            $pdf_content = $pdf->Output($pdf_filename, 'S');

            Storage::put('private_uploads/eula-pdfs/' .$pdf_filename, $pdf_content);


            $acceptance->accept($sig_filename, $item->getEula(), $pdf_filename, $request->input('note'));

            // Send the PDF to the signing user
            if (($request->input('send_copy') == '1') && ($assigned_user->email !='')) {

                // Add the attachment for the signing user into the $data array
                $data['file'] = $pdf_filename;
                try {
                    $assigned_user->notify((new AcceptanceAssetAcceptedToUserNotification($data))->locale($assigned_user->locale));
                } catch (\Exception $e) {
                    Log::warning($e);
                }
            }
            try {
                $acceptance->notify((new AcceptanceAssetAcceptedNotification($data))->locale(Setting::getSettings()->locale));
            } catch (\Exception $e) {
                Log::warning($e);
            }
            event(new CheckoutAccepted($acceptance));

            $return_msg = trans('admin/users/message.accepted');

        // Item was not accepted
        } else {

            if (Setting::getSettings()->require_accept_signature == '1') {

                // The item was declined, check for a signature
                if ($request->filled('signature_output')) {
                    $sig_filename = 'siglog-' . Str::uuid() . '-' . date('Y-m-d-his') . '.png';
                    $data_uri = $request->input('signature_output');
                    $encoded_image = explode(',', $data_uri);
                    $decoded_image = base64_decode($encoded_image[1]);
                    Storage::put('private_uploads/signatures/' . $sig_filename, (string)$decoded_image);

                    // No image data is present, kick them back.
                    // This mostly only applies to users on super-duper crapola browsers *cough* IE *cough*
                } else {
                    return redirect()->back()->with('error', trans('general.shitty_browser'));
                }
            }
            
            // Format the data to send the declined notification
            $branding_settings = SettingsController::getPDFBranding();
            $assigned_to = User::find($acceptance->assigned_to_id)->present()->fullName;

            $data = [
                'item_tag' => $item->asset_tag,
                'item_model' => $item->model ? $item->model->name : $item->display_name,
                'item_serial' => $item->serial,
                'item_status' => $item->assetstatus?->name,
                'note' => $request->input('note'),
                'declined_date' => Carbon::parse($acceptance->declined_at)->format('Y-m-d'),
                'signature' => ($sig_filename) ? storage_path() . '/private_uploads/signatures/' . $sig_filename : null,
                'assigned_to' => $assigned_to,
                'company_name' => $branding_settings->site_name,
                'date_settings' => $branding_settings->date_display_format,
                'qty' => $acceptance->qty ?? 1,
            ];


            for ($i = 0; $i < ($acceptance->qty ?? 1); $i++) {
                $acceptance->decline($sig_filename, $request->input('note'));
            }

            $acceptance->notify(new AcceptanceAssetDeclinedNotification($data));
            Log::debug('New event acceptance.');
            event(new CheckoutDeclined($acceptance));
            $return_msg = trans('admin/users/message.declined');
        }

        if ($acceptance->alert_on_response_id) {
            try {
                $recipient = User::find($acceptance->alert_on_response_id);

                if ($recipient) {
                    Log::debug('Attempting to send email acceptance.');
                    Mail::to($recipient)->send(new CheckoutAcceptanceResponseMail(
                        $acceptance,
                        $recipient,
                        $request->input('asset_acceptance') === 'accepted',
                    ));
                    Log::debug('Send email notification sucess on checkout acceptance response.');
                }
            } catch (Exception $e) {
                Log::error($e->getMessage());
                Log::warning($e);
            }
        }

        return redirect()->to('account/accept')->with('success', $return_msg);

    }

}
