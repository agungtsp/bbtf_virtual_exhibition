window.addEventListener('DOMContentLoaded', (event) => {
    let usersRequest = new CometChat.UsersRequestBuilder().setLimit(30).build();
   console.log(usersRequest);
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
                var groupName = GROUP_NAME;
                var groupType = CometChat.GROUP_TYPE.PUBLIC;
                var password = "";

                var group = new CometChat.Group(GUID, groupName, groupType, password);

                CometChat.createGroup(group).then();
                group.setIcon(group_logo_url);
                CometChat.updateGroup(group).then();
                let usersRequest = new CometChat.UsersRequestBuilder().setLimit(0).build();
                let membersList = [
                  new CometChat.GroupMember("admin", CometChat.GROUP_MEMBER_SCOPE.PARTICIPANT),
                  new CometChat.GroupMember(UID, CometChat.GROUP_MEMBER_SCOPE.PARTICIPANT),
                ];
                CometChat.addMembersToGroup(GUID, membersList, []).then(
                    () => {
                        CometChat.transferGroupOwnership(GUID, "admin").then(
                            () => {
                                CometChat.leaveGroup(GUID).then();
                            }
                        );
                    }
                );

                CometChatWidget.launch({
                    "widgetID": "590bc393-f078-494a-8ee8-c777b33978f3",
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