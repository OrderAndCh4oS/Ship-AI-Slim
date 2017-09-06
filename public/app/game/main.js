new Vue({
    el: '#root',
    data: {
        squadrons: [],
        players: [],
        numberOfPlayers: 2
    },
    mounted() {
        for(let i = 0; i < this.numberOfPlayers; i++) {
            this.players.push({
                squadron: {},
                id: 0
            })
        }
    }
});