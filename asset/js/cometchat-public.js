window.addEventListener('DOMContentLoaded', (event) => {    
    var appSetting = new CometChat.AppSettingsBuilder().subscribePresenceForAllUsers().setRegion(appRegion).build();
    CometChat.init(appID, appSetting).then();
    CometChatWidget.init({
        "appID": appID,
        "appRegion": appRegion,
        "authKey": authKey
    }).then(response => {
        CometChatWidget.createOrUpdateUser({
            uid: UID,
            name: USERNAME,
            avatar: AVATAR
        }).then((user) => {
            if(AVATAR){
                user.setAvatar(AVATAR);
                 CometChat.updateCurrentUserDetails(user).then(
                    user => {
                        console.log("user updated", user);
                    }, error => {
                        console.log("error", error);
                    }
                )
            }
            CometChatWidget.login({
                uid: UID,
            }).then((loggedInUser) => {
                let membersList = [
                    new CometChat.GroupMember(UID, CometChat.GROUP_MEMBER_SCOPE.PARTICIPANT)
                ];
                var general = GENERAL;
                CometChat.addMembersToGroup('general', membersList, []).then();
                CometChatWidget.launch({
                    "widgetID": "93767f54-5a9c-4c07-978a-044cd584ce8a",
                    "docked": "true",
                    "alignment": "right", //left or right
                    "roundedCorners": "true",
                    "height": "450px",
                    "width": "400px",
                    "defaultID": general, //default UID (user) or GUID (group) to show,
                    "defaultType": 'group' //user or group
            });
            // if(AVATAR){
            //     var user = new CometChat.User(UID);

            //     user.setAvatar(AVATAR);
            //     console.log(user)
            //     CometChat.updateCurrentUserDetails(user).then(
            //         user => {
            //             console.log("user updated", user);
            //         }, error => {
            //             console.log("error", error);
            //         }
            //     )
            // }
        }, error => {
        });
    });

}, error => {
});

});