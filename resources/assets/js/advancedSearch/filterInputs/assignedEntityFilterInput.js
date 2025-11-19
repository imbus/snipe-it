import { TextFilterInput } from "./textFilterInput"

class AssignedEntityFilterInput extends TextFilterInput {
    getValue() {
        const value = this.hasValue() ? this.element.value : null;
        const type = document.getElementById(this.element.id + "_type").value;

        if (!value || !type) {
            return;
        }

        return {
            type: type,
            value: value
        }

    }

    setValue(newValue, logic, operator) {
        return new Promise((resolve, reject) => {
            try {
                queueMicrotask(() => {
                    this.element.value = newValue.value;
                    document.getElementById(this.element.id + "_type").value = newValue.type;
                    this.setSearchOperator(logic, operator)
                });

            }
            catch (e) {
                reject(e);
            }
            resolve(newValue);
        })
    } 

    clear() {
        this.element.value = "";
        document.getElementById(this.element.id + "_type").value = "";
        super.clear();
    }
}

export {
    AssignedEntityFilterInput,
};