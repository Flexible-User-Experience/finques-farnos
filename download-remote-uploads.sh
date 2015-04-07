#!/bin/bash

rsync -hva --ignore-existing flux@s3.flux.cat:/home/flux/webapps/finques-farnos/shared/web/uploads/ web/uploads

#scp flux@s3.flux.cat:/home/flux/webapps/museums/shared/web/uploads/museums/logos/* web/uploads/museums/logos
#scp flux@s3.flux.cat:/home/flux/webapps/museums/shared/web/uploads/extensions/photos/* web/uploads/extensions/photos
#scp flux@s3.flux.cat:/home/flux/webapps/museums/shared/web/uploads/museums/photos/* web/uploads/museums/photos
#scp flux@s3.flux.cat:/home/flux/webapps/museums/shared/web/uploads/objects/photos/* web/uploads/objects/photos
#scp flux@s3.flux.cat:/home/flux/webapps/museums/shared/web/uploads/spaces/photos/* web/uploads/spaces/photos
