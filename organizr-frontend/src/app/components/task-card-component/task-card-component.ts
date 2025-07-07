import { Component, Input, Output, EventEmitter, HostListener } from '@angular/core';
import { CommonModule, DatePipe } from '@angular/common';
import { Task } from '../../models/task-model.model';

@Component({
  selector: 'app-task-card',
  standalone: true,
  imports: [CommonModule, DatePipe],
  templateUrl: './task-card-component.html',
  styleUrls: ['./task-card-component.scss']
})
export class TaskCardComponent {
  @Input() task!: Task;
  @Output() edit = new EventEmitter<void>();
  @Output() delete = new EventEmitter<void>();

  isMenuOpen = false;

  toggleMenu(event: MouseEvent): void {
    event.stopPropagation();
    this.isMenuOpen = !this.isMenuOpen;
  }

  @HostListener('document:click')
  closeMenu() {
    this.isMenuOpen = false;
  }

  onEdit(event: MouseEvent): void {
    event.stopPropagation();
    this.edit.emit();
    this.isMenuOpen = false;
  }

  onDelete(event: MouseEvent): void {
    event.stopPropagation();
    this.delete.emit();
    this.isMenuOpen = false;
  }
}