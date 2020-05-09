import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OptionsTableComponent } from './options-table.component';

describe('OptionsTableComponent', () => {
  let component: OptionsTableComponent;
  let fixture: ComponentFixture<OptionsTableComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OptionsTableComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OptionsTableComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
