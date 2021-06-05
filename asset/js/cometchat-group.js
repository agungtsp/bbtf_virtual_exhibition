
window.addEventListener('DOMContentLoaded', (event) => {
//     let usersRequest = new CometChat.UsersRequestBuilder().setLimit(30).build();
//    console.log(usersRequest);
    var appSetting = new CometChat.AppSettingsBuilder().subscribePresenceForAllUsers().setRegion(appRegion).build();
    CometChat.init(appID, appSetting).then();
    CometChatWidget.init({
        "appID": appID,
        "appRegion": appRegion,
        "authKey": authKey
    }).then(response => {
        CometChatWidget.login({
            uid: "admin",
        }).then((loggedInUser) => {
            console.log(loggedInUser);
            var groupName = GROUP_NAME;
            var groupType = CometChat.GROUP_TYPE.PUBLIC;
            var password = "";

            var group = new CometChat.Group(GUID, groupName, groupType, password);

            CometChat.createGroup(group).then(
                () => {
                    console.log(group);
                    group.setIcon(group_logo_url);
                    CometChat.updateGroup(group).then(
                        () => {
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
                        }
                    );
                    show_widget_chat();
                }, error => {
                    show_widget_chat();
                }
            );
        });
    });

});
function show_widget_chat(){
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
                var adminName = ADMIN_NAME;
                var limit = 100;
                var usersRequest = new CometChat.UsersRequestBuilder().setLimit(limit).build();

                usersRequest.fetchNext().then(
                userList => {
                    /* userList will be the list of User class. */
                    console.log("User list received:", userList);
                    if (adminName) {
                        var adminID = userList.find(x => x.name === adminName);
                        if (adminID === undefined) {
                            adminID = 'admin';
                        } else {
                            adminID = adminID.uid;
                        }
                    } else {
                        var adminID = 'admin';
                    }
                    /* retrived list can be used to display contact list. */
                  
                    CometChatWidget.launch({
                        "widgetID": "590bc393-f078-494a-8ee8-c777b33978f3",
                        "docked": "true",
                        "alignment": "right", //left or right
                        "roundedCorners": "true",
                        "height": "450px",
                        "width": "400px",
                        "defaultID": adminID, //default UID (user) or GUID (group) to show,
                        "defaultType": 'user' //user or group
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
                },
                  error => {
                    console.log("User list fetching failed with error:", error);
                    location.reload();
                  }
                );
            }, error => {
            });
        });
    }, error => {
    });
}
function add_friend(){
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
                var limit = 30;
                var groupMemberRequest = new CometChat.GroupMembersRequestBuilder(GUID)
                .setLimit(limit)
                .build();

                groupMemberRequest.fetchNext().then(
                    groupMembers => {
                        $.each(groupMembers, function( index, value ) {
                            if(value.status=="offline"){
                                console.log(value.status);
                                console.log(value.uid);
                            } else {
                                
                            }
                        });
                    },
                    error => {
                        console.log("Group Member list fetching failed with exception:", error);
                    }
                );
            });
        });
    });
}