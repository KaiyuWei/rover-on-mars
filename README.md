## About The App
This is a cli where you can calculate the robot's location by inputing the initial status of robots, and giving some instructions about turning and moving to them.

## How To Start The App
Start the app using Docker by following steps:
- Build a docker image: `docker build . -t rover-on-mars:latest`
- Run a docker container: `docker run -d --name rover-on-mars rover-on-mars:latest`
- Enter shell: `docker exec -it rover-on-mars /bin/bash`

## How To Use
### Run the command
Run the command `php artisan robot:go [options]` to get the final locations of the robots you input
### Input format
The input should be a multi-line string like:
```angular2html
5 5
1 2 N
LMLMLMLMM
3 3 E
MMRMMRMRRM
```
The first line of input is the upper-right coordinates of the plateau, the lower-left coordinates are assumed to be 0, 0.

The rest of the input is information pertaining to the rovers that have been deployed. Each rover has two lines of input. The first line gives the rover's position, and the second line is a series of instructions telling the rover how to explore the plateau.

The position is made up of two integers and a letter separated by spaces, corresponding to the x and y coordinates and the rover's orientation.

### Input source
The default input file is 
