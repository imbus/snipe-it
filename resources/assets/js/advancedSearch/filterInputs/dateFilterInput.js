import { FilterInput } from "./filterInput";

class DateFilterInput extends FilterInput {
    constructor(el, apiService) {
        super(el, apiService);

        // assign as class properties
        this.startDatepickerInput = document.getElementById(el.id + "_start");
        this.endDatepickerInput = document.getElementById(el.id + "_end");

        if (this.startDatepickerInput) {
            this.startDatepicker = $(this.startDatepickerInput).datepicker({
                todayBtn: "linked",
                clearBtn: true,
                disableTouchKeyboard: true,
                forceParse: false,
                keepEmptyValues: true,
                daysOfWeekHighlighted: "0,6",
                todayHighlight: true,
                format: "yyyy-mm-dd",
            });
        }

        if (this.endDatepickerInput) {
            this.endDatepicker = $(this.endDatepickerInput).datepicker({
                todayBtn: "linked",
                clearBtn: true,
                disableTouchKeyboard: true,
                forceParse: false,
                keepEmptyValues: true,
                daysOfWeekHighlighted: "0,6",
                todayHighlight: true,
                format: "yyyy-mm-dd",
            });
        }

        // event listeners (check element exists)
        if (this.startDatepickerInput) {
            $(this.startDatepickerInput).on('changeDate', (event) => {
                this.startDate = new Intl.DateTimeFormat('en-CA').format(event.date);
            });

            $(this.startDatepickerInput).on('clearDate', (_) => {
                this.startDate = undefined;
            });
        }

        if (this.endDatepickerInput) {
            $(this.endDatepickerInput).on('changeDate', (event) => {
                this.endDate = new Intl.DateTimeFormat('en-CA').format(event.date);
            });

            $(this.endDatepickerInput).on('clearDate', (_) => {
                this.endDate = undefined;
            });
        }
    }


    getValue() {
        const result = {};

        if (this.startDate != undefined) {
            result.startDate = this.startDate;
        }
        if (this.endDate != undefined) {
            result.endDate = this.endDate;
        }
        return result;
    }

    setValue(newValue, logic, operator) {

        return new Promise((resolve, reject) => {
            try {

                if (newValue.startDate != undefined) {
                    const startDateObject = new Date(newValue.startDate);
                    this.startDatepicker.datepicker('setDate', startDateObject);
                }

                if (newValue.endDate != undefined) {
                    const endDateObject = new Date(newValue.endDate);
                    this.endDatepicker.datepicker('setDate', endDateObject);
                }

                this.setSearchOperator(logic, operator);
                resolve(newValue);
            }
            catch (e) {
                console.error('Error setting dates:', e);
                reject(e);
            }
        });
    }

    clear() {
        const r = this.getValue();
        if (r.startDate !== undefined || jQuery.isEmptyObject(r) === false) {
            this.startDatepicker.datepicker('clearDates');
            this.startDate = undefined;
        }
        if (r.endDate !== undefined || jQuery.isEmptyObject(r) === false) {
            this.endDatepicker.datepicker('clearDates');
            this.endDate = undefined;
        }

        super.clear();
    }
}

export {
    DateFilterInput,
};