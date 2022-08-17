import React, { PureComponent } from 'react';
import Item from './Item';
import CommonFunctions from '@jsroot/common/common-functions';
import { MobileMaxWidth } from '@jsroot/common/constants';

export default class Grid extends PureComponent {
    constructor(props) {
        super(props);

        this.state = {
            activeIndex: 0,
            isMobile: window.innerWidth <= MobileMaxWidth ? true : false
        };
    }

    componentDidMount() {
        window.addEventListener('resize', this.handleResize);
    }

    componentWillUnmount() {
        window.removeEventListener('resize', this.handleResize);
    }

    handleResize = () => {
        const isMobile = this.state.isMobile;
        if (!isMobile && window.innerWidth <= MobileMaxWidth) {
            this.setState({ isMobile: true });
        } else {
            if (isMobile && window.innerWidth > MobileMaxWidth) {
                this.setState({ isMobile: false });
            }
        }
    };

    handleActiveTab = isNext => {
        const matches = this.props.matches;
        const activeIndex = this.state.activeIndex;
        const currentIndex = isNext ? activeIndex + 1 : activeIndex - 1;
        if((isNext && currentIndex < matches.length) || (!isNext && currentIndex >= 0))  {
            this.setState({activeIndex: currentIndex});
        }
    };

    render() {
        const { matches } = this.props;
        const { activeIndex, isMobile } = this.state;
        return (
            <div className="draw" style={{transform: `${isMobile ? `translateX(-${activeIndex}00%)` : 'none'}`}}>
                {
                    matches.map((item, index) => {
                        let first = '';
                        let last = '';
                        if(index === 0) {
                            first = 'draw__round--first';
                        }
                        if(index === matches.length - 1) {
                            last = 'draw__round--last';
                        }
                        return (
                            <div className={`draw__round ${first} ${last}`}  key={item.key}>
                                <div className="draw__header">
                                    {
                                        activeIndex > 0 &&
                                        <i className="draw__arrow draw__arrow--previous icon-livescore icon-chevron-left" onClick={() => this.handleActiveTab(false)}/>
                                    }
                                    <div className="draw__label">{CommonFunctions.getStageName(item.stage)}</div>
                                    {
                                        activeIndex < matches.length - 1 &&
                                        <i className="draw__arrow draw__arrow--next icon-livescore icon-chevron-right" onClick={() => this.handleActiveTab(true)} />
                                    }
                                </div>
                                <div className="draw__brackets">
                                    {
                                        item.matches.map((match, matchIndex) => {
                                            const positionClass = (matchIndex + 1) % 2 ? 'draw__bracket--odd': 'draw__bracket--even';
                                            return (
                                                <Item match={match} positionClass={positionClass} key={matchIndex} index={matchIndex}/>
                                            );
                                        })
                                    }
                                </div>
                            </div>
                        );
                    })
                }
            </div>
        )
    }
}