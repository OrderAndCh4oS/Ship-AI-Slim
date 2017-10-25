[![MIT Licence](https://badges.frapsoft.com/os/mit/mit.svg?v=103)](https://opensource.org/licenses/mit-license.php)

# Getting Started
`composer install`

Add database details to `src/settings.php`

`doctrine orm:schema-tool:create`

# TODO

## BASE REQUIREMENTS

- [x] Add way to spend cash to upgrade drones
- [ ] Add way to buy additional drones
- [ ] Add way to rename drones
- [x] Get drones from API to populate the game
- [ ] Update drone data based on game result
- [ ] Update squadron cash based on game result
- [ ] Track rounds played and rounds won by each squadron

## API

- [ ] Data Transformers with Fractal
- [ ] Handle JSON Validation
- [ ] Implement JWT web tokens for authentication

## GAME ADDITIONS

- [ ] Add Hull integrity for health of drone
- [ ] Add Shield/Armour value to block damage
- [ ] Workout way to gauge hit strength based on velocity and angle
- [ ] Try to determine the aggressor and give bonus to attack
- [ ] Add deflections after hit detection based on velocity and angle
- [ ] Leader Board for top squadrons based on squad value, calculated by cost of upgrades and number of drones
