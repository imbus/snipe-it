<?php

namespace Tests\Feature\Notifications\Email;

use App\Events\CheckoutableCheckedIn;
use App\Mail\CheckinAssetMail;
use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[Group('notifications')]
class EmailNotificationsToAdminAlertEmailUponCheckinTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
    }

    public function test_admin_alert_email_sends()
    {
        $this->settings->enableAdminCC('cc@example.com');

        $user = User::factory()->create();
        $asset = Asset::factory()->assignedToUser($user)->create();

        $asset->model->category->update(['checkin_email' => true]);

        $this->fireCheckInEvent($asset, $user);

        Mail::assertSent(CheckinAssetMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->hasCc('cc@example.com');
        });
    }

    public function test_admin_alert_email_still_sent_when_category_email_is_not_set_to_send_email_to_user()
    {
        $this->settings->enableAdminCC('cc@example.com');

        $category = Category::factory()->create([
            'checkin_email' => false,
            'eula_text' => null,
            'use_default_eula' => false,
        ]);
        $assetModel = AssetModel::factory()->create(['category_id' => $category->id]);
        $asset = Asset::factory()->create(['model_id' => $assetModel->id]);

        $this->fireCheckInEvent($asset, User::factory()->create());

        Mail::assertSent(CheckinAssetMail::class, function ($mail) {
            return $mail->hasTo('cc@example.com');
        });
    }

    public function test_admin_alert_email_still_sent_when_user_has_no_email_address()
    {
        $this->settings->enableAdminCC('cc@example.com');

        $user = User::factory()->create(['email' => null]);
        $asset = Asset::factory()->assignedToUser($user)->create();

        $asset->model->category->update(['checkin_email' => true]);

        $this->fireCheckInEvent($asset, $user);

        Mail::assertSent(CheckinAssetMail::class, function ($mail) {
            return $mail->hasTo('cc@example.com');
        });
    }

    private function fireCheckInEvent($asset, $user): void
    {
        event(new CheckoutableCheckedIn(
            $asset,
            $user,
            User::factory()->checkinAssets()->create(),
            ''
        ));
    }
}
