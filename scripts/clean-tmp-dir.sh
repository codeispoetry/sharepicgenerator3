#!/bin/bash
find ./tmp -name "*.png" -type f -mtime +3 -exec rm {} \;
