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
      this.updateUrls(newId);
      this.id = newId;
    }
  }

  private _url: string = '';
  id: string = '';

  bannerUrl: string = '';
  imageUrl: string = '';
  iframeCode: string = '';
  imageIframeCode: string = '';

  static getBannerUrl(id: string): string {
    return AppComponent.getUrl('banner', id);
  }

  static getImageUrl(id: string): string {
    return AppComponent.getUrl('image', id);
  }

  static getUrl(type: string, id: string): string {
    return 'https://ndsmyter.be/local-guides-banner/' + type + '.php?id=' + id;
  }

  ngOnInit(): void {
  }

  updateUrls(id: string): void {
    const imageUrl = AppComponent.getImageUrl(id);
    const bannerUrl = AppComponent.getBannerUrl(id);
    this.bannerUrl = bannerUrl;
    this.imageUrl = imageUrl;
    this.iframeCode = '<iframe src="' + bannerUrl + '"></iframe>';
    this.imageIframeCode = '<a href="https://www.google.com/maps/contrib/' + id + '"><img src="' + imageUrl + '" alt="Generated using https://ndsmyter.be/local-guides-banner/"></a>';
  }

}
