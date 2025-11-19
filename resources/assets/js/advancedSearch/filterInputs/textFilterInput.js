import { FilterInput } from "./filterInput";

class TextFilterInput extends FilterInput {
    getValue() {
        return this.hasValue() ? this.element.value : null;
    }
    clear() {
        this.element.value = "";
        super.clear();
    }
}

export {
    TextFilterInput,
};