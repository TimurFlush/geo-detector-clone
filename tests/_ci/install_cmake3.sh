#!/usr/bin/env bash

# trace ERR through pipes
set -o pipefail

# trace ERR through 'time command' and other functions
set -o errtrace

# set -u : exit the script if you try to use an uninitialised variable
set -o nounset

# set -e : exit the script if any statement returns a non-true return value
set -o errexit

cd $HOME

wget https://launchpad.net/ubuntu/+archive/primary/+files/cmake3_3.5.1-1ubuntu3~14.04.1_amd64.deb

sudo apt install ./cmake3_3.5.1-1ubuntu3~14.04.1_amd64.deb -y
sudo apt-get update
sudo apt-get install cmake3