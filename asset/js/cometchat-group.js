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
            CometChatWidget.login({
                uid: UID,
            }).then((loggedInUser) => {
                var GUID = GUID;
                var groupName = GROUP_NAME;
                var groupType = CometChat.GROUP_TYPE.PUBLIC;
                var password = "";

                var group = new CometChat.Group(GUID, groupName, groupType, password);

                CometChat.createGroup(group).then();
                CometChatWidget.launch({
                    "widgetID": "93767f54-5a9c-4c07-978a-044cd584ce8a",
                    "docked": "true",
                    "alignment": "right", //left or right
                    "roundedCorners": "true",
                    "height": "450px",
                    "width": "400px",
                    "defaultID": GUID, //default UID (user) or GUID (group) to show,
                    "defaultType": 'group' //user or group
                });
                if(AVATAR){
                    var user = new CometChat.User(UID);
    
                    user.setAvatar(AVATAR);
                    CometChat.updateCurrentUserDetails(user).then(
                        // user => {
                        //     console.log("user updated", user);
                        // }, error => {
                        //     console.log("error", error);
                        // }
                    )
                }

                
        }, error => {
        });
    });

}, error => {
});

});