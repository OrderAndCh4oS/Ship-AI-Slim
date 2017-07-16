window.onload = function () {
    var canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        width = canvas.width = window.innerWidth,
        height = canvas.height = window.innerHeight,
        ships = [],
        deadShips = [],
        countdownInit = new Date().getTime(),
        scores = {red: 0, green: 0, blue: 0, pink: 0, yellow: 0, cyan: 0},
        scoresShown = false;

    function isInArray(value, array) {
        return array.indexOf(value) > -1
    }

    function countdown() {
        var currentCountdown = 30 - Math.round((new Date().getTime() - countdownInit) / 1000)
        return currentCountdown <= 0 ? 0 : currentCountdown;
    }

    function incrementScore(ship) {
        switch (ship.colour) {
            case '#ff3243':
                scores.red++;
                break;
            case '#ff4fa9':
                scores.pink++;
                break;
            case '#61ff83':
                scores.green++;
                break;
            case '#6ddfff':
                scores.cyan++;
                break;
            case '#4b6aff':
                scores.blue++;
                break;
            case '#ffe76a':
                scores.yellow++;
                break
        }
    }

    function displayScores() {
        if (scoresShown) {
            return;
        }
        var scoresList, title, scoresHolder, restartLink, backLink;
        scoresShown = true;
        scoresHolder = document.createElement('scoresHolder');
        scoresHolder.classList.add('holder');
        title = document.createElement('h1');
        title.classList.add('scores-title');
        title.innerHTML = "Final Scores";
        scoresList = document.createElement('scoresList');
        for (var key in scores) {
            var li = document.createElement('li');
            li.innerHTML = key + ": " + scores[key];
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

    function setShipColour(ship) {
        switch (i % 6) {
            case 0:
                ship.colour = '#ff3243';
                break;
            case 1:
                ship.colour = '#ff4fa9';
                break;
            case 2:
                ship.colour = '#61ff83';
                break;
            case 3:
                ship.colour = '#6ddfff';
                break;
            case 4:
                ship.colour = '#4b6aff';
                break;
            case 5:
                ship.colour = '#ffe76a';
                break
        }
    }

    function instantiateShip() {
        var ship = shipClass.create(Math.random() * (width),
            Math.random() * (height), (Math.random() * 0.2) + 0.1,
            (Math.random() * 0.5) + 0.5);
        ship.friction = 0.99;
        setShipColour(ship);
        ship.id = i;
        ship.angle = i % 2 === 0 ? 0 : Math.PI;
        ships.push(ship)
        return ship;
    }

    function drawBackground() {
        context.clearRect(0, 0, width, height);
        context.fillStyle = '#000';
        context.fillRect(0, 0, width, height);
    }

    function displayCountdown(timer) {
        context.font = '20px Verdana';
        context.fillStyle = '#ff3243';
        context.fillText(timer, 30, 30);
    }

    function howClose(x, y) {
        return Math.atan2(Math.sin(x - y), Math.cos(x - y))
    }

    function findClosestShip(ship, closestShip, j, i) {
        var distance = ship.distanceTo(ships[j]);
        if (!closestShip.distance && i !== j) {
            closestShip.angle = ships[j].angleToPredictedLocation(ship);
            closestShip.distance = distance
        }
        if (distance < closestShip.distance && i !== j) {
            closestShip.angle = ships[j].angleToPredictedLocation(ship);
            closestShip.distance = distance
        }
    }

    function detectShipCollision(distance, i, j) {
        if (distance < 12 && i !== j && !isInArray(i, deadShips) &&
            !isInArray(j, deadShips)) {
            if (Math.random >= 0.5) {
                deadShips.push(j);
                ships[i].kills++;
                incrementScore(ships[i]);
            } else {
                deadShips.push(i);
                ships[j].kills++;
                incrementScore(ships[j]);
            }
        }
    }

    function loopShipIfLeftScreen(ship) {
        if (ship.position.getX() > width) {
            ship.position.setX(0)
        }
        if (ship.position.getX() < 0) {
            ship.position.setX(width)
        }
        if (ship.position.getY() > height) {
            ship.position.setY(0)
        }
        if (ship.position.getY() < 0) {
            ship.position.setY(height)
        }
    }

    function setShipTurningSpeed(ship, angleToClosest) {
        switch (true) {
            case (howClose(ship.angle, angleToClosest) >= 0.6):
                ship.turnLeft(ship.turningSpeed * 0.1);
                break;
            case (howClose(ship.angle, angleToClosest) >= 0.4):
                ship.turnLeft(ship.turningSpeed * 0.1 / 3 * 2);
                break;
            case (howClose(ship.angle, angleToClosest) >= 0.2):
                ship.turnLeft(ship.turningSpeed * 0.1 / 3);
                break;
            case (howClose(ship.angle, angleToClosest) > 0):
                ship.turnLeft(howClose(ship.angle, angleToClosest));
                break;
            case (howClose(ship.angle, angleToClosest) <= -0.6):
                ship.turnRight(ship.turningSpeed * 0.1);
                break;
            case (howClose(ship.angle, angleToClosest) <= -0.4):
                ship.turnRight(ship.turningSpeed * 0.1 / 3 * 2);
                break;
            case (howClose(ship.angle, angleToClosest) <= -0.2):
                ship.turnRight(ship.turningSpeed * 0.1 / 3);
                break;
            case (howClose(ship.angle, angleToClosest) < 0):
                ship.turnRight(howClose(ship.angle, angleToClosest));
                break;
            default:
                ship.stopTurning()
        }
    }

    function setShipThrusters(ship, angleToClosest) {
        if (howClose(ship.angle, angleToClosest) <= 0.3 &&
            howClose(ship.angle, angleToClosest) >= -0.3) {
            ship.startThrusting(0.5)
        } else if (howClose(ship.angle, angleToClosest) <= 0.15 &&
            howClose(ship.angle, angleToClosest) >= -0.15) {
            ship.startThrusting(0.8)
        } else if (howClose(ship.angle, angleToClosest) <= 0.1 &&
            howClose(ship.angle, angleToClosest) >= -0.1) {
            ship.startThrusting(1)
        } else {
            ship.stopThrusting()
        }
    }

    function drawExplosion(index) {
        context.save();
        context.translate(ships[index].position.getX(),
            ships[index].position.getY());
        context.beginPath();
        context.arc(0, 0, 20, 0, 2 * Math.PI);
        context.fillStyle = '#ff6b1b';
        context.fill();
        context.restore();
    }

    function removeDeadShips() {
        deadShips.sort();
        for (var k = deadShips.length - 1; k >= 0; k--) {
            var index = deadShips[k];
            drawExplosion(index);
            ships.splice(index, 1);
            deadShips.pop()
        }
    }

    function showUI() {
        var currentCountdown = countdown();
        if (currentCountdown === 0) {
            displayScores();
        }
        displayCountdown(currentCountdown);
    }

    function update() {
        drawBackground();
        for (var i = ships.length - 1; i >= 0; i--) {
            var ship = ships[i],
                closestShip = {
                    distance: false,
                    angle: 0
                };

            for (var j = ships.length - 1; j >= 0; j--) {
                if (ships[j].colour !== ship.colour) {
                    findClosestShip(ship, closestShip, j, i);
                    detectShipCollision(closestShip.distance, i, j);
                }
            }

            setShipTurningSpeed(ship, closestShip.angle);
            setShipThrusters(ship, closestShip.angle);

            ship.update();
            ship.draw(context);
            loopShipIfLeftScreen(ship);
        }

        removeDeadShips();

        showUI();

        requestAnimationFrame(update)
    }

    for (var i = 90; i > 0; i--) {
        var ship = instantiateShip();
    }

    update();
};


