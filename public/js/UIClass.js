var UIClass = {
    countdownInit: null,
    width: null,
    height: null,

    create: function (width, height) {
        var obj = Object.create(this);
        obj.countdownInit = new Date().getTime();
        obj.width = width;
        obj.height = height;
        return obj;
    },

    countdown: function () {
        var currentCountdown = 30 - Math.round((new Date().getTime() - this.countdownInit) / 1000);
        return currentCountdown <= 0 ? 0 : currentCountdown;
    },

    displayCountdown: function (timer, context) {
        context.font = '20px Verdana';
        context.fillStyle = '#ff3243';
        context.fillText(timer, 30, 30);
    },

    drawBackground: function (context) {
        context.clearRect(0, 0, this.width, this.height);
        context.fillStyle = 'gold';
        context.fillRect(0, 0, this.width, this.height);
    },

    showUI: function (scores, context) {
        var currentCountdown = this.countdown();
        this.displayCountdown(currentCountdown, context);
        if (currentCountdown === 0 && !scores.isDisplayed) {
            scores.displayScores();
        }
    }
};