new Vue({
    el: '#root',

    data: {
        showMenu: true,
        squadrons: [],
        players: [],
        playerCount: 2
    },

    methods: {
        nextSquadron(player) {
            player.index = (player.index + 1) % this.squadrons.length;
            player.squadron = this.squadrons[player.index];
        },
        prevSquadron(player) {
            player.index = (player.index + this.squadrons.length - 1) % this.squadrons.length;
            player.squadron = this.squadrons[player.index];
        },
        updatePlayerCount() {
            if (this.playerCount > this.players.length) {
                this.addPlayer()
            } else {
                this.players.splice(-1, 1);
            }
        },
        addPlayer() {
            this.players.push({
                index: 0,
                squadron: {
                    name: "Choose Squadron"
                }
            })
        },
        play() {
            this.showMenu = false;
            let ids = this.players.map(p => p.squadron.id);
            startGame(ids);
        }
    },

    mounted() {
        for (let i = 0; i < this.playerCount; i++) {
            this.addPlayer();
        }
        axios.get('/api/v1/squadrons')
            .then(response => {
                this.squadrons = response.data.data;
                console.log(this.squadrons);


            })
            .catch(error => console.log(error))
    }
});