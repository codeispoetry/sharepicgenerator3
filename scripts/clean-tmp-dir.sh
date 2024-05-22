#!/bin/bash
find /srv/sharepic/tmp -name "*.png" -type f -mtime +3 -exec rm {} \;
