# Tenderhandler (learning project)

## Assignment

### Goal
Tracking the fulfillment and payout of tenders.

### Required Features
* For All Users
  * sign up
    * take password twice
    * encrypt password
    * feedback about successful/failed registration
  * log in/out
    * feedback about success/failure
  * read data (R)
    * display tabular data by months:
      * number of milestones due (based on Milestone.date)
    * display tabular data by milestones:
      * number of submitted documents
      * sum of completed payments
    * display tabular data by milestones:
      * number of documents required
      * number of documents submitted
* For Administrators
  * insert data (C)
    * tender data --> into database
    * milestones --> for tender
    * required documents --> for milestone
  * read data (R)
    * display tabular data by managers:
      * number of tenders managed
  * modify data (U)
    * set the manager of each tender
  * delete data (D)
    * delete milestone (if no document has been submitted yet)
* For Managers
  * modify data (U)
    * upload file for document requirement

## Design

### ER diagram

![tenderEK_2 drawio](https://github.com/mabense/tenderhandler/assets/102444418/3ab43b28-fa4a-4deb-a377-ce8e68ceec34)

...

## Implementation

### Environment

PHP, MySQL, HTML

### Tools

XAMPP, Visual Studio Code

### Currently hosted [here](https://l-com.hu/mabense/tenderhandler)
