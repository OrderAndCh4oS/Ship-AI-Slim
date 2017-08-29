new Vue({
    el: '#root',

    data: {
        form: new Form({
            csrf_name: '',
            csrf_value: '',
            name: ''
        }),
        drones: {}
    },

    mounted() {
        axios.get('/api/v1/squadrons/' + 14)
            .then(response => {
                console.log(response);
                this.squadrons = response.data.data;
            })
            .catch(error => console.log(error))
    },

    methods: {
        onSubmit() {
            this.form.submit('post', '/api/v1/squadrons')
                .then(data => {
                    this.squadrons.push(data.data);
                })
                .catch(errors => console.log(errors));
        },
    },
});