The module allows to add Activity, History and Tasks blocks to any entity (including self-created activities), and displays them in the right panel of that entity's detailed layout.

In order to implement this, the module creates all necessary links between the participating entities and automatically changes the configuration of the page panel layout for the corresponding entity.

## Installation Guide

- In order to install the module, open the Module Manager in Administration and carry out the installation.
![Install Activites Module](_assets/installation.png)
- In order to update the module, select the "Update" option from the module's action menu and then klick on the button "Run Update".
- In order to uninstall the module, select the "Remove" option from the module's action menu.

## Administrator Guide 
- After installing the module, you can enable or disable tasks in the settings of the individual entities. If these are activated, the tasks incl. the entire history and are displayed in the right side panel. 

In order to enable tasks in an entity: 

1. Open the Entity Manager in the Administration and go to the entity settings where activities are to be activated by clicking on "Edit".

2. In the Settings section tick the checkbox "Tasks" in order to be able to manage the tasks connected with the appropriate entity.

![Edit Entity Activities Active](_assets/edit-entity-task.jpg)

### Configuration of Access Rights for Users

In order to enable users to use activities and tasks, please configure the access rights to the following entities for them:

- Calls
- Meetings
- Tasks
- E-Mails

## User Guide

After activating activities and tasks, the following blocks appear in the right side panel:

![Activities Panel All](_assets/activities-panel-all.jpg)

### Side Panel for Activities

- On the activities page panel you can see the entries for all types of activities or filtered by meetings or calls by clicking on the appropriate button. By default, all activities are displayed.

  ![History Panel](_assets/history-panel.jpg)

- In order to create a new activity, click on the Select icon in the action menu in the upper right corner of the panel and select the appropriate action.

  ![new activity](_assets/new-activity.png)

- To create and send a new e-mail, select the item `Compose Email`  from the action menu.
![compose_email](_assets/compose-email.png)

- To  create a new call, select `Schedule Call`.
  ![create_call](_assets/create-call.png)

- To create a new meeting, select `Schedule Meeting`.
![create_meeting](_assets/create-meeting.png)

- To view detailed information about a specific entity, click on the name of that activity.  

- To  display quickly an entry in a pop-up, click on "View" in the action menu for each entry. 

- To  edit quickly an entry in a pop-up, click on "Edit" in the action menu for each entry.

- To delete an entry, click on "Remove" in the action menu of this entry.

- It is possible to mark a specific activity (call or meeting) as the one, which has taken place or has not taken place, in order to do that you should select the appropriate option from the action menu.  
  ![Set Held](_assets/set-held.png)

### Side Panel for History

- The History page panel displays the course of all activities (by default). 
- To filter the history by a specific activity type, click the Meetings, Calls or E-mails button accordingly.  
- To save information about a call, a meeting, or an e-mail that has already been sent, click on the Select icon in the upper right corner and select the appropriate option.  

  ![log_history](_assets/log-history.png)
  
- To view detailed information about a specific entity, click on the name of that activity. 
- The following actions are available for every activity entry from the history: quick view of the entry, quick editing of the entry and deletion of the entry. 

![history_single_actions](_assets/history-single-actions.png)

### Side Panel for Tasks

- The task side panel displays current and completed tasks, indicating the Task name, responsible employee, task status and due date.

  ![task_list](_assets/task-list.png)

- The following options are available in the action menu for each individual entry: task view, task processing, mark task as completed, delete task. 

- To  create quickly a new task entry, click on the "+" icon in the upper right corner. 
  
  ![Task Panel](_assets/task-panel.jpg)


