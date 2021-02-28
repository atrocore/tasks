The module allows to add Activity, History and Tasks blocks to any entity (including self-created activities), and displays them in the right panel of that entity's detailed layout.

In order to implement this, the module creates all necessary links between the participating entities and automatically changes the configuration of the page panel layout for the corresponding entity.

## Installation Guide

In order to install the module, open the Module Manager in Administration and carry out the installation.
![Install Activites Module](_assets/installation.png)
In order to update the module, select the "Update" option from the module's action menu and then klick on the button "Run Update".
In order to uninstall the module, select the "Remove" option from the module's action menu.

## Administrator Guide 
After installing the module, you can enable or disable tasks in the settings of the individual entities. If these are activated, the tasks incl. the entire history and are displayed in the right side panel. 

In order to enable tasks in an entity: 

1. Open the Entity Manager in the Administration and go to the entity settings where activities are to be activated by clicking on "Edit".

2. In the Settings section tick the checkbox "Tasks" in order to be able to manage the tasks connected with the appropriate entity.

![Edit Entity Activities Active](_assets/edit-entity-task.jpg)

### Configuration of Access Rights for Users

In order to enable users to use tasks, please configure their access rights to the entity "Tasks".

## User Guide

After activating activities and tasks, the following block appears in the right side panel:

![Activities Panel All](_assets/task-side-panel.png)

### Side Panel for Tasks

The task side panel displays current and completed tasks, indicating the task name, responsible employee, task status and due date.

The following options are available in the action menu for each individual entry: view, edit, mark task as completed, delete. 

To  create quickly a new task entry, click on the "+" icon in the upper right corner. 
  
![Task Panel](_assets/task-new.png)

It is also possible to view or edit the task in the main window, click on the button "Full Form" on the top left for it. 
![Task Panel](_assets/task-new-full.png)

### Dashboard Widget for Tasks

The User can add a Dashlet for the Dashboard to see his own tasks. 

![Task Panel](_assets/tasks-widget.png)
