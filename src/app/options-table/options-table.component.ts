import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-options-table',
  templateUrl: './options-table.component.html',
  styleUrls: ['./options-table.component.css']
})
export class OptionsTableComponent implements OnInit {


  optionsTable: any = {
    headers: ['field', 'default_value', 'required', 'description'],
    values: [
      {
        field: 'id',
        required: 'Yes',
        default_value: '',
        description: 'Your unique Google Maps ID'
      },
      {
        field: 'bg',
        default_value: 'f1a340',
        required: 'No',
        description: 'Choose the background color'
      },
      {
        field: 'fg',
        default_value: '000000',
        required: 'No',
        description: 'Choose the text color'
      },
      {
        field: 's',
        default_value: '0',
        required: 'No',
        description: 'The size: 0=default, 1=small, 2=smallest'
      }
    ]
  };

  constructor() { }

  ngOnInit() {
  }

}
