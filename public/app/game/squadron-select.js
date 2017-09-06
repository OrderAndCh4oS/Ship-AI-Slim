Vue.component('squadron-select', {
    props: ['player'],
    template: `
        <div class="squadron-select">
            <input type="text" name="squadron-name" class="squadron-name" :value="player.squadron" v-model="player.squadron" disabled>
            <button @click="$emit('prev')">↑</button>
            <button @click="$emit('next')">↓</button>
        </div>
   `
});