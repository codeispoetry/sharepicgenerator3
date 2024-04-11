#!/bin/bash

# Variables
ssh_handle="sharepic-verdigado"
local_dir="users/localhorst/save/"
remote_dir="/srv/sharepic/users"

rm -rf $local_dir/*
ssh ${ssh_handle} "find ${remote_dir} -type d -name 'bug'" | while read -r dir
do
  # Download the directory
  info_json=$(rsync -az --info=NAME ${ssh_handle}:${dir}/* ${local_dir} | grep info.json)

  user=${dir#/srv/sharepic/users/}
  user=${user%/bug}

  number=${info_json%/info.json}
  info="${user}:${number}"

  sed -i "s/bughtml/${info//\//\\/}/g" $local_dir/$info_json
  # Delete the directory from the remote server
  #ssh ${ssh_handle} "rm -rf ${dir}"
done