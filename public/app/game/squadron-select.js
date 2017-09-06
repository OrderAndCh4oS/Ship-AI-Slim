Vue.component('squadron-select', {
    props: ['player'],
    template: `
        <div class="squadron-select">
            <input type="text" class="squadron-name" v-text="squadron" v-model="player.squadron" disabled>Choose Squadron</input>
            <button @click="lastTeam">↑</button>
            <button @click="nextTeam">↓</button>
        </div>
   `
});