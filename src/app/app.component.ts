import {Component, OnInit, Pipe, PipeTransform} from '@angular/core';
import {DomSanitizer} from '@angular/platform-browser';

@Pipe({name: 'safe'})
export class SafePipe implements PipeTransform {
  constructor(private sanitizer: DomSanitizer) {
  }

  transform(url) {
    return this.sanitizer.bypassSecurityTrustResourceUrl(url);
  }
}

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent implements OnInit {
  get url(): string {
    return this._url;
  }

  set url(value: string) {
    this._url = value;
    const match = new RegExp('contrib/(\\d+)').exec(value);
    let newId = match && match[1] ? match[1] : null;
    if (this.id != newId) {
      this.updateBanner(newId);
      this.updateIframeCode(newId);
      this.id = newId;
    }
  }

  // baseUrl: string = 'https://ndsmyter.be/local-guides-banner/banner.php?id=';
  baseUrl: string = 'http://localhost/local-guides-banner/banner.php?id=';
  private _url: string = '';
  id: string = '';

  bannerUrl: string = '';
  iframeCode: string = '';

  ngOnInit(): void {
    this.url = 'https://www.google.com/maps/contrib/100683510490650445783/edits/@50.9668175,3.4090764,9z/data=!3m1!4b1!4m3!8m2!3m1!1e1';
  }

  updateBanner(id: string): void {
    this.bannerUrl = this.baseUrl + id;
  }

  updateIframeCode(id: string): void {
    this.iframeCode = '<iframe src="' + this.baseUrl + id + '"></iframe>';
  }
}
