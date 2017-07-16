window.onload = function () {
    var canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        width = canvas.width = window.innerWidth,
        height = canvas.height = window.innerHeight,
        ships = [],
        deadShips = [],
        countdownInit = new Date().getTime(),
        scores = scoreClass.create();

    for (var i = 90; i > 0; i--) {
        instantiateShip();
    }

    update();

    function update() {
        drawBackground();
        showUI();

        for (var i = ships.length - 1; i >= 0; i--) {
            var ship = ships[i],
                closestShip = {
                    distance: false,
                    angle: 0
                };

            for (var j = ships.length - 1; j >= 0; j--) {
                if(containsDeadShip(i, j)) {
                    continue;
                }
                if (ships[j].colour !== ship.colour) {
                    findClosestShip(ship, closestShip, j, i);
                    detectShipCollision(closestShip.distance, i, j);
                }
            }

            setShipTurningSpeed(ship, closestShip.angle);
            setShipThrusters(ship, closestShip.angle);

            ship.update();
            ship.draw(context);
            loopShipIfLeftDrawingArea(ship);
        }

        removeDeadShips();

        requestAnimationFrame(update)
    }

    function isInArray(value, array) {
        return array.indexOf(value) > -1
    }

    function containsDeadShip(i, j) {
        return isInArray(i, deadShips) || isInArray(j, deadShips);
    }

    function countdown() {
        var currentCountdown = 30 - Math.round((new Date().getTime() - countdownInit) / 1000);
        return currentCountdown <= 0 ? 0 : currentCountdown;
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
                break;
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
        ships.push(ship);
        return ship;
    }

    function drawBackground() {
        context.clearRect(0, 0, width, height);
        context.fillStyle = '#000';
        context.fillRect(0, 0, width, height);
    }

    function showUI() {
        var currentCountdown = countdown();
        displayCountdown(currentCountdown);
        if (currentCountdown === 0 && !scores.isDisplayed) {
            scores.displayScores();
        }
    }

    function displayCountdown(timer) {
        context.font = '20px Verdana';
        context.fillStyle = '#ff3243';
        context.fillText(timer, 30, 30);
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
        if (distance < 12 && i !== j && !isInArray(i, deadShips) && !isInArray(j, deadShips)) {
            if (Math.random >= 0.5) {
                deadShips.push(j);
                ships[i].kills++;
                scores.incrementScore(ships[i].colour);
            } else {
                deadShips.push(i);
                ships[j].kills++;
                scores.incrementScore(ships[j].colour);
            }
        }
    }

    function loopShipIfLeftDrawingArea(ship) {
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

    function howClose(x, y) {
        return Math.atan2(Math.sin(x - y), Math.cos(x - y))
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
};


