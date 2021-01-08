import {
  trigger,
  transition,
  style,
  query,
  animate,
} from '@angular/animations';

export const fader = trigger('routeAnimations', [
  transition('* <=> *', [
    // Set a default  style for enter and leave
    query(':enter, :leave', [
      style({
        position: 'absolute',
        width: '100%',
        opacity: 0,
        transform: 'scale(0) translateY(100%)',
      }),
    ]),
    // Animate the new page in
    query(':enter', [
      animate(
        '600ms ease',
        style({
          opacity: 1,
          transform: 'scale(1) translateY(0)',
          left: 0,
          top: '50%',
        })
      ),
    ]),
  ]),
]);