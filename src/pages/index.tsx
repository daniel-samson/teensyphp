import type {ReactNode} from 'react';
import clsx from 'clsx';
import Link from '@docusaurus/Link';
import useDocusaurusContext from '@docusaurus/useDocusaurusContext';
import Layout from '@theme/Layout';
import HomepageFeatures from '@site/src/components/HomepageFeatures';
import Heading from '@theme/Heading';

import styles from './index.module.css';

function HomepageHeader() {
  return (
    <header className={clsx('hero', styles.heroBanner)}>
      <div className={styles.heroContent}>
        <div className={styles.buttons}>
          <Link
            className="button button--secondary button--lg"
            to="/docs/intro">
            Get Started
          </Link>
        </div>
      </div>
    </header>
  );
}

export default function Home(): ReactNode {
  const {siteConfig} = useDocusaurusContext();
  return (
    <Layout
      title="TeensyPHP - Lightweight PHP Micro Framework"
      description="A micro web framework for rapidly creating REST APIs and hypermedia applications">
      <HomepageHeader />
      <main>
        <div className={styles.taglineSection}>
          <Heading as="h2" className={styles.taglineHeading}>
            {siteConfig.tagline}
          </Heading>
        </div>
        <HomepageFeatures />
      </main>
    </Layout>
  );
}
