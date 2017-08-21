var scoreClass = {
    scores: {red: 0, green: 0, blue: 0, pink: 0, yellow: 0, cyan: 0},
    isDisplayed: false,
    create: function () {
        return Object.create(this);
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
    makeTitle: function () {
        var title;
        title = document.createElement('h1');
        title.classList.add('scores-title');
        title.innerHTML = "Final Scores";
        return title;
    },
    makeLink: function (title, href, className) {
        var link = document.createElement('a');
        link.innerHTML = title;
        link.setAttribute('href', href);
        link.classList.add(className);
        return link;
    },
    makeScoresList: function () {
        var scoresList = document.createElement('ul');
        for (var key in this.scores) {
            if (this.scores.hasOwnProperty(key)) {
                var li = document.createElement('li');
                li.innerHTML = key + ": " + this.scores[key];
                li.classList.add(key);
                scoresList.appendChild(li);
            }
        }
        return scoresList;
    },
    makeScoresHolder: function (title, scoresList, backLink, restartLink) {
        var scoresHolder = document.createElement('div');
        scoresHolder.classList.add('holder');
        scoresHolder.appendChild(title);
        scoresHolder.appendChild(scoresList);
        scoresHolder.appendChild(backLink);
        scoresHolder.appendChild(restartLink);
        return scoresHolder;
    },
    displayScores: function () {
        var scoresList, title, scoresHolder, restartLink, backLink;
        this.isDisplayed = true;
        title = this.makeTitle();
        scoresList = this.makeScoresList();
        backLink = this.makeLink("Back to Menu", '/', 'back-link');
        restartLink = this.makeLink("Play Again", 'drone-ai', 'restart-link');
        scoresHolder = this.makeScoresHolder(title, scoresList, backLink, restartLink);
        document.body.appendChild(scoresHolder);
    }
};