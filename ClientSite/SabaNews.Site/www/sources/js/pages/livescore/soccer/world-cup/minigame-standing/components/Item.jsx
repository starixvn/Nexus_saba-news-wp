import React, { PureComponent, Fragment } from 'react';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { ActionCreators } from '../actions';

export class Item extends PureComponent {
    constructor(props) {
        super(props);
    }

    handleClick = (number) =>{
        const display = document.getElementsByClassName('detail')[number].style.display;
        if(display === 'block'){
            document.getElementsByClassName('detail')[number].style.display = 'none';
        }
        else{
            document.getElementsByClassName('detail')[number].style.display = 'block';
        }
    };

    render() {
        const { standing, number } = this.props;
        return (
            <div>
                <div style={{display:'flex', justifyContent:'space-between'}}>
                    <div>{number + 1}</div>
                    <div style={{display:'flex', justifyContent:'space-between'}}>
                        <img src={standing.icon}></img>
                        <p>{standing.userName}</p>
                    </div>
                    <div>{standing.point}</div>
                    <div>{standing.updatedDate != null ? standing.updatedDate : standing.createdDate}</div>
                    <div>{window.stage === 4 ? <img id={"iconDetail" + (number + 1)} src={standing.icon} onClick={e => this.handleClick(number)}></img> : null}</div>
                </div>
                {
                    window.stage === 4 && 
                    <div className="detail" style={{display:'none', justifyContent:'space-between'}}>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Vòng</div>
                            <div>Diểm</div>
                        </div>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Vòng 1/8</div>
                            <div>{standing.detail.pointEightTeamStage}</div>
                        </div>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Tứ Kết</div>
                            <div>{standing.detail.pointQuarterfinalsStage}</div>
                        </div>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Bán Kết</div>
                            <div>{standing.detail.pointSemifinalsStage}</div>
                        </div>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Tranh Hạng 3</div>
                            <div>{standing.detail.pointThirdPlace}</div>
                        </div>
                        <div style={{display:'flex', justifyContent:'space-between'}}>
                            <div>Chung Kết</div>
                            <div>{standing.detail.pointFinalStage}</div>
                        </div>
                    </div>
                }
            </div>
        );
    }
}

const mapDispatchToProps = dispatch => bindActionCreators(ActionCreators, dispatch);

const mapStateToProps = state => {
    const { matches, stages, league, votes } = state;
    return { matches, stages, league, votes };
};

export default connect(mapStateToProps, mapDispatchToProps)(Item);