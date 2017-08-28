Vue.component('squadron-item', {
    props: ['href'],
    template: `<p>
                    <a :href="href"><slot></slot></a>
               </p>`,
});