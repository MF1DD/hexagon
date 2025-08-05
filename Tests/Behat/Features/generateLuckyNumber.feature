Feature: Generiere eine Glückszahl

  Scenario: Eine gültige Glückszahl erzeugen
    Given ich sende eine GET-Anfrage an "/lucky-number"
    Then sollte der JSON-Response enthalten:
      | luckyNumber | int |
      | isLucky     | bool |