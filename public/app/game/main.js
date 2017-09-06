new Vue({
    el: '#root',
    data: {
        squadrons: {},
        players: [],
        numberOfPlayers: 2
    },
    methods: {
        nextSquadron(player, index) {
            this.players[player] = this.squadrons[(index + 1) % this.squadrons.length]
        },
        prevSquadron(player, index) {
            this.players[player] = this.squadrons[(index - 1) % this.squadrons.length]
        }
    },
    mounted() {
        axios.get('/api/v1/squadrons')
            .then(response => {
                this.squadrons = response.data.data;
                for (let i = 0; i < this.numberOfPlayers; i++) {
                    this.players.push({
                        squadron: 'Choose Squadron',
                        id: 0
                    })
                }
            })
            .catch(error => console.log(error))
    }
});