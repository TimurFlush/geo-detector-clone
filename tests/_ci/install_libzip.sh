#!/usr/bin/env bash

# trace ERR through pipes
set -o pipefail

# trace ERR through 'time command' and other functions
set -o errtrace

# set -u : exit the script if you try to use an uninitialised variable
set -o nounset

# set -e : exit the script if any statement returns a non-true return value
set -o errexit

mkdir $HOME/libzip && cd $HOME/libzip

git clone https://github.com/nih-at/libzip.git && cd libzip
mkdir build && cd build

sudo cmake ..
sudo make
sudo make install