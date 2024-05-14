import React, { Component } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';
import LiveScore from './LiveScore';
import Players from './Players';


const tabs = [
    {
        id : 1,
        slug : 'lich-thi-dau',
        name : 'Lịch thi đấu'
    },
    {
        id : 2,
        slug : 'ket-qua',
        name : 'Kết quả'
    },
    {
        id : 3,
        slug : 'cau-thu',
        name : 'Cầu Thủ'
    },
    {
        id : 4,
        slug : 'tieu-su',
        name : 'Tiểu sử'
    }
];  
export class AppContainer extends Component {
    constructor(props) {
        super(props); 
        this.state = {
            active: 1
        },
        this.handleSelectTab = this
            .handleSelectTab
            .bind(this);
    }
    

    componentDidMount() {
        this.props.getTeamData(window.teamId);
    }

    handleSelectTab = key => {
        this.setState({
            active : key
        })
        this.renderContent(key);
    }

    renderContent = () =>  {
        const { preMatches, postMatches, players, team } = this.props;
        switch (this.state.active) {
            case 1:
                return(
                    preMatches !== null  && <LiveScore matches={preMatches}/>
                )
            case 2:
                return (
                    <div>Kết quả</div>
                )
            case 3:
                return (
                    players && <Players data={players}/>
                )
            case 4:
                return(
                    <div>Tiểu Sử</div>
                )
            default : 
                return(
                    <LiveScore matches={groups}/>
                )
        }
    }

    render() {
        return (
            <div className="block-info-team">
                <div className="container">
                    <div className="info-main-team">
                        <div className="team-header">
                            <div className="name-team-detail">
                                <div className="team-detail-logo">logo</div>
                                <div className="team-detail-name">ABC</div>
                            </div>
                            <div className="team-menu-tabs">
                                <ul className="nav-team">
                                    {
                                        tabs.map(item => (
                                            <li key={item.id} onClick={() => this.handleSelectTab(item.id)} className={`nav-team-item ${this.state.active === item.id ? 'active' : ''}`}>
                                                {item.name}
                                            </li>
                                        ))
                                    }
                                    
                                </ul>
                            </div>
                        </div>
                        <div className="team-body">
                            {this.renderContent()}
                        </div> 
                    </div>
                </div>
            </div>
        )
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { preMatches, postMatches , players, team } = state;
    return { preMatches, postMatches, players, team };
};

export default connect(mapStateToProps, mapDispatchToProps)(AppContainer);