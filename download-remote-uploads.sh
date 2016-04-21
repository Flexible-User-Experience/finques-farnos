#!/bin/bash

rsync -hva --ignore-existing flux@s3.flux.cat:/home/flux/webapps/finques-farnos/shared/web/uploads/ web/uploads

