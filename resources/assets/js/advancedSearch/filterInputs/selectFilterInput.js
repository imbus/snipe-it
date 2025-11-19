import { FilterInput } from "./filterInput";

class SelectFilterInput extends FilterInput {

    getValue() {
        const selections = $(this.element).select2('data');

        const selectedValues = selections.map(item => {
            const parseId = parseInt(item.id);
            return isNaN(parseId) ? item.id : parseId;
        })

        if (selectedValues.length === 0) {
            return null;
        }

        return selectedValues;
    }

    setValue(newValues, logic, operator, type = this.getType()) {
        const requestPromises = newValues.map((newValue) => {
            // If it's a number, fetch from backend
            if (typeof newValue === "number") {
                return this.apiService.fetchItemFromBackendById(type, newValue)
                    .then((response) => {
                        return response.json().then((responseJson) => {
                            const $existingOption = $(this.element).find(`option[value='${responseJson.id}']`);

                            if ($existingOption.length === 0) {
                                const option = new Option(responseJson.name, responseJson.id, true, true);
                                $(this.element).append(option);
                            } else {
                                $existingOption.prop('selected', true);
                            }

                            this.setSearchOperator(logic, operator);

                            $(this.element).trigger('change');
                            return responseJson;
                        });
                    });
            } else {
                queueMicrotask(() => {
                    // Directly insert/select string value
                    this.setSearchOperator(logic, operator);
                    const existingOption = $(this.element).find(`option[value='${newValue}']`);

                    if (existingOption.length === 0) {
                        const option = new Option(newValue, newValue, true, true);
                        $(this.element).append(option);
                    } else {
                        existingOption.prop('selected', true);
                    }

                    $(this.element).trigger('change');

                    // Return a resolved promise for consistency
                    return Promise.resolve({ id: newValue, name: newValue });
                });
            }
        });

        return Promise.all(requestPromises);
    }



    clear() {
        $(this.element).val(null).trigger('change');
        super.clear();
    }
}

export {
    SelectFilterInput,
};