# WooCommerce Realtime Sales Tracker

## Overview

The "WooCommerce Realtime Sales Tracker" plugin integrates WooCommerce with Firebase, enabling the storage of sales data into a Firebase Realtime Database. It also includes functionality to retrieve data from Firebase and send daily sales summaries via email.

## Installation

1. **Firebase Setup**:
    - Create a Firebase Project: Go to the Firebase Console ([Firebase Console](https://console.firebase.google.com/)), click on "Add project" or select an existing one, and follow the setup prompts.
    - Create Realtime Database: In the Firebase Console, navigate to the "Database" section, and click on "Create Database". Choose your preferred settings and location.

2. **Plugin Installation**:
    - Install the plugin in WordPress.
    - Access the settings page in the WordPress admin dashboard to configure Firebase settings. This includes specifying the Firebase Realtime Database URL and the associated API key.

## Core Functionality

- **Order Data Capture**: Upon order completion, the plugin extracts relevant information such as customer details, order total, and purchased products from WooCommerce. This data is formatted and stored in the Firebase Realtime Database under the 'purchases' node, organized by date.

- **Daily Sales Summary**: The plugin includes a scheduled task to compile a daily sales summary. At the end of each day, it retrieves sales data from Firebase for the current date and calculates total sales quantity and revenue. This information is compiled into an email summary and sent to a designated email address.

## Firebase Database Setup

1. **Database Rules**: After creating the database, you can set access rules in the "Rules" tab. For development, you can allow read and write access to everyone:

```json
{
  "rules": {
    ".read": true,
    ".write": true,
    "purchases": {
      ".indexOn": "date"
    }
  }
}