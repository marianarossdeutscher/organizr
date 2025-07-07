import { Component, Input, Output, EventEmitter } from '@angular/core';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-drawer',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './drawer-component.html',
  styleUrls: ['./drawer-component.scss']
})
export class DrawerComponent {
  @Input() isCollapsed: boolean = false;

  @Output() logoutClicked = new EventEmitter<void>();
  @Output() toggleClicked = new EventEmitter<void>();

  onLogout(): void {
    this.logoutClicked.emit();
  }

  onToggle(): void {
    this.toggleClicked.emit();
  }
}