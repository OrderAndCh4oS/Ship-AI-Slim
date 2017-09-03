Vue.component('squadron-list', {
    props: ['squadrons'],

    template: `<div class="squadron-list">
                    <squadron-item v-for="squadron in squadrons" 
                        :key="squadron.id" 
                        :id="squadron.id">{{squadron.name}}</squadron-item>
               </div>`
});