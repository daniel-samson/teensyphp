import type {ReactNode} from 'react';
import clsx from 'clsx';
import Heading from '@theme/Heading';
import styles from './styles.module.css';

type FeatureItem = {
  title: string;
  description: ReactNode;
};

const FeatureList: FeatureItem[] = [
  {
    title: 'Lightweight',
    description: (
      <>
        Minimal overhead with a functional-first approach. TeensyPHP gets out of
        your way so you can focus on building your application.
      </>
    ),
  },
  {
    title: 'Simple Routing',
    description: (
      <>
        Clean and intuitive routing with URL parameters, middleware support, and
        content negotiation built right in.
      </>
    ),
  },
  {
    title: 'Extensible',
    description: (
      <>
        Easily inject or replace framework functionality with function autoloading.
        Use only what you need.
      </>
    ),
  },
];

function Feature({title, description}: FeatureItem) {
  return (
    <div className={clsx('col col--4')}>
      <div className="text--center padding-horiz--md">
        <Heading as="h3">{title}</Heading>
        <p>{description}</p>
      </div>
    </div>
  );
}

export default function HomepageFeatures(): ReactNode {
  return (
    <section className={styles.features}>
      <div className="container">
        <div className="row">
          {FeatureList.map((props, idx) => (
            <Feature key={idx} {...props} />
          ))}
        </div>
      </div>
    </section>
  );
}
