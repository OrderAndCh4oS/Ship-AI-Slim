var scoreClass = {
    scores: {red: 0, green: 0, blue: 0, pink: 0, yellow: 0, cyan: 0},
    isDisplayed: false,

    create: function () {
        var obj = Object.create(this);
        return obj;
    },

    incrementScore: function (colour) {
        switch (colour) {
            case '#ff3243':
                this.scores.red++;
                break;
            case '#ff4fa9':
                this.scores.pink++;
                break;
            case '#61ff83':
                this.scores.green++;
                break;
            case '#6ddfff':
                this.scores.cyan++;
                break;
            case '#4b6aff':
                this.scores.blue++;
                break;
            case '#ffe76a':
                this.scores.yellow++;
                break
        }
    },

    displayScores: function () {
        var scoresList, title, scoresHolder, restartLink, backLink;
        this.isDisplayed = true;
        scoresHolder = document.createElement('div');
        scoresHolder.classList.add('holder');
        title = document.createElement('h1');
        title.classList.add('scores-title');
        title.innerHTML = "Final Scores";
        scoresList = document.createElement('ul');
        for (var key in this.scores) {
            var li = document.createElement('li');
            li.innerHTML = key + ": " + this.scores[key];
            li.classList.add(key);
            scoresList.appendChild(li);
        }
        backLink = document.createElement('a');
        backLink.innerHTML = "Back to Menu";
        backLink.classList.add('back-link');
        backLink.setAttribute('href', '/');
        restartLink = document.createElement('a');
        restartLink.innerHTML = "Play Again";
        restartLink.classList.add('restart-link');
        restartLink.setAttribute('href', 'ship-ai');
        scoresHolder.appendChild(title);
        scoresHolder.appendChild(scoresList);
        scoresHolder.appendChild(backLink);
        scoresHolder.appendChild(restartLink);
        document.body.appendChild(scoresHolder);
    }
}