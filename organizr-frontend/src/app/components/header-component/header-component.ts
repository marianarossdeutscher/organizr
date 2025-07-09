import { Component, Input, Output, EventEmitter, inject } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router } from '@angular/router';

export interface UserProfile {
  name: string;
  avatarUrl?: string;
}

@Component({
  selector: 'app-header',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './header-component.html',
  styleUrls: ['./header-component.scss']
})
export class HeaderComponent {
  @Input() user: UserProfile | null = { name: 'User Name' };
  @Output() searchTermChanged = new EventEmitter<string>();

  private router = inject(Router);

  onSearch(event: Event): void {
    const input = event.target as HTMLInputElement;
    this.searchTermChanged.emit(input.value);
  }

  goToSettings(): void {
    this.router.navigate(['/settings']);
  }
}