import React, { PureComponent } from 'react';
import TableHead from './table/Head';
import TableBody from './table/Body';
import { Tabs, Tab } from 'react-bootstrap';


export default class GridItem extends PureComponent {
    constructor(props) {
        super(props);
        this.totalItem = props.standings.length - 1;
        this.state = {
            selectedTab: 0
        }
    }

    handleSelectTab = key => {
        this.setState({selectedTab: key});
    };

    handleNextTab = () => {
        const { selectedTab } = this.state;
        if(selectedTab < this.totalItem) {
            this.setState({selectedTab: selectedTab  + 1});
        }
    };

    handlePrevTab = () => {
        const { selectedTab } = this.state;
        if(selectedTab > 0) {
            this.setState({selectedTab: selectedTab  - 1});
        }
    };


    render() {
        const { standings } = this.props;
        const { selectedTab } = this.state;
        console.log(selectedTab);
        return (
            <div className="group-standing">
                <span className={`btn-btn-standing prev-standing ${selectedTab === 0 ? 'disabled': '' }`} onClick={this.handlePrevTab}><i className="icon-chevron-left" /></span>
                <Tabs activeKey={selectedTab} onSelect={this.handleSelectTab}  id="livescore-tab-league-detail"
                    className="live-score-nav"
                >
                    {
                        standings.map((group, index) => {
                            const groupName = group.key.charAt(0);
                            return (
                                <Tab title={groupName} eventKey={index} key={index}>
                                    <div className="table-custom">
                                        <TableHead groupName={groupName} isMobile={true} />
                                        <TableBody teams={group.childs} isMobile={true} />
                                    </div>
                                </Tab>
                            );
                        })
                    }
                </Tabs>
                <span className={`btn-btn-standing next-standing ${selectedTab >= this.totalItem ? 'disabled': '' }`} onClick={this.handleNextTab}><i className="icon-chevron-right" /></span>
            </div>
        );
    }
}
