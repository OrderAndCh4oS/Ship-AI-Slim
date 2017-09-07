Vue.component('squadron-select', {
    props: ['player'],
    template: `
        <div class="squadron-select">
            <p class="squadron-name">{{player.squadron.name}}</p>
            <button @click="$emit('prev', player)">↑</button>
            <button @click="$emit('next', player)">↓</button>
        </div>
   `
});