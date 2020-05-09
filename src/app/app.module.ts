import {NgModule} from '@angular/core';

import {AppComponent, SafePipe} from './app.component';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {MatInputModule} from '@angular/material';
import {FormsModule} from '@angular/forms';
import {AdsenseModule} from 'ng2-adsense';
import { MatTableModule } from '@angular/material/table';
import { OptionsTableComponent } from './options-table/options-table.component';

@NgModule({
  declarations: [
    AppComponent,
    SafePipe,
    OptionsTableComponent
  ],
    imports: [
        BrowserAnimationsModule, FormsModule,
        MatInputModule,
        AdsenseModule.forRoot({
            adClient: 'ca-pub-8927382453106522',
            adSlot: '9915477379'
        }), MatTableModule
    ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule {
}
