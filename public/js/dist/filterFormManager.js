import {
    FilterInput,
    SelectFilterInput,
    AssignedEntityFilterInput,
    DateFilterInput,
    TextFilterInput
} from '/js/dist/filterInputs.min.js';
import { container } from '/js/dist/simpleDIContainer.min.js';

export default class FilterFormManager {
    constructor() {
        this.filters = [];
        this.inputs = [];
        this.apiService = container.resolve("apiService");;
    }

    collect() {
        this.filters = [];
        this.inputs = [];

        // Select2
        document.querySelectorAll('select[id^="advancedSearch_"]').forEach(el => {
            if (el.id === 'advancedSearch_assigned_to') {
                this.inputs.push(new AssignedEntityFilterInput(el, this.apiService));
            } else {
                this.inputs.push(new SelectFilterInput(el, this.apiService));
            }
        });

        // Dates
        document.querySelectorAll('input[id^="advancedSearch_"][id$="_start"][type="date"], input[id^="advancedSearch_"][id$="_end"][type="date"]').forEach(el => {
            this.inputs.push(new DateFilterInput(el, this.apiService));
        });

        // Text
        document.querySelectorAll('input[id^="advancedSearch_"][type="text"]').forEach(el => {
            this.inputs.push(new TextFilterInput(el, this.apiService));
        });

        // Process all inputs polymorphically
        this.inputs.forEach(input => {
            input.appendTo(this.filters);
        });

        return this.filters;
    }

    clearAll() {
        this.collect();
        this.inputs.forEach(field => {
            field.clear();
        });
    }

    async setValuesFromResponse(response) {
        this.clearAll();

        const promises = [];
        for (const [field, value] of Object.entries(response)) {

            const input = this.inputs.find(input => input.key === field);
            if (!input) {
                console.warn(`No input found for key: ${field}`);
                Livewire.dispatch('showNotification', { type: 'error', message: "Failed to apply predefined filter" });
                continue;
            }

            try {
                const result = input.setValue(value);
                if (result instanceof Promise) {
                    promises.push(result);
                }
            } catch (err) {
                console.error(`Failed to set value for "${field}":`, err);
                Livewire.dispatch('showNotification', { type: 'error', message: 'Failed to apply predefined filter' });
            }
        }


        // Wait for all async setValue calls to complete
        await Promise.all(promises);
        this.setAdvancedSearchPanelFilterEnabledState(false);
    }

    setAdvancedSearchPanelFilterEnabledState(state) {
        const fields = document.getElementById("advancedSearchPanel").getElementsByTagName('*');
        for (let i = 0; i < fields.length; i++) {
            fields[i].disabled = state;
        }
    }
}