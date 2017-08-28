class Errors {

    constructor() {
        console.log("Errors");
        this.errors = {};
    }

    get (field) {
        if (this.errors[field]) {
            return this.errors[field];
        }
    }

    record(errors) {
        let arr = {};
        for (let i = 0; i < errors.length; i++) {
            arr[errors[i].field] = errors[i].message;
        }
        this.errors = arr;
    }

    clear(field) {
        if (field)
            delete this.errors[field];
        else
            this.errors = {};
    }

    has(field) {
        return this.errors.hasOwnProperty(field)
    }

    any() {
        return Object.keys(this.errors).length > 0;
    }
}