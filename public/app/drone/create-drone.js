new Vue({
    el: '#root',

    data: {
        form: new Form({
            drones: []
        }),
        squadron: {}
    },

    mounted() {
        if (squadronID) {
            axios.get('/api/v1/squadrons/' + squadronID)
                .then(response => {
                    this.squadron = response.data.data;
                    this.form.drones = this.squadron.drones;
                })
                .catch(error => console.log(error));
        }
    },

    methods: {
        onSubmit() {
            this.form.submit('put', '/api/v1/squadrons/' + squadronID + '/drones')
                .then(data => {
                    this.squadron = data.data;
                    this.form.drones = this.squadron.drones;
                    this.form.messages = data.messages;
                })
                .catch(errors => console.log(errors));
        },
        enter() {
            console.log('fired');
            setTimeout(() => this.form.messages = [], 3000)
        }
    },
});