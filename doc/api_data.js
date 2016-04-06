define({ "api": [
  {
    "type": "post",
    "url": "/login",
    "title": "Login to the system",
    "name": "PostLogin",
    "group": "User",
    "version": "0.0.0",
    "filename": "rest/versions/v1/controllers/AuthController.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://farm.shop4u.by/rest/web/index.php/v1/login"
      }
    ]
  },
  {
    "type": "post",
    "url": "/register-device",
    "title": "Register new device",
    "name": "PostRegisterDevice",
    "group": "User",
    "permission": [
      {
        "name": "none"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "allowedValues": [
              "1",
              "2"
            ],
            "optional": false,
            "field": "type",
            "description": "<p>Type of the device, 1 for Android and 2 for IOS</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "push_token",
            "description": "<p>Push token for push-notifications</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "device_id",
            "description": "<p>The id of device in the system</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"device_id\": \"sdgsdfg1shsdhsdhs2dhsdgsd2fgshsdhs4dhsdh\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "json",
            "optional": false,
            "field": "ValidationFailed",
            "description": "<p>Wrong data entry.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 422 Validation failed\n{\n  \"field\": \"type\",\n  \"message\": \"Значение type неверно\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "rest/versions/v1/controllers/AuthController.php",
    "groupTitle": "User",
    "sampleRequest": [
      {
        "url": "http://farm.shop4u.by/rest/web/index.php/v1/register-device"
      }
    ]
  }
] });
