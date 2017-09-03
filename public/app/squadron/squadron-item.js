Vue.component('squadron-item', {
    props: ['id'],
    template: `<p>
                    <a :href="url"><slot></slot></a>
               </p>`,
    computed: {
        url: function () {
            return '/manage-squadrons/' + this.id
        }
    }
});